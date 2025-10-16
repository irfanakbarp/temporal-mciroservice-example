package controllers

import (
	"fmt"
	"time"

	"goravel/app/http/requests/contacts"
	"goravel/app/models"
	"goravel/app/temporal"
	"goravel/app/traits"

	"github.com/goravel/framework/contracts/http"
	"github.com/goravel/framework/facades"
	"github.com/goravel/framework/validation"
	"go.temporal.io/sdk/client"
)

type ContactController struct {
	response traits.ResponseAPI
}

func NewContactController() *ContactController {
	return &ContactController{
		response: traits.ResponseAPI{},
	}
}

func (r *ContactController) CreateContact(ctx http.Context) http.Response {
	request := &contacts.CreateContactRequest{}

	// Validasi request
	validator, err := facades.Validation().Make(
		ctx.Request().All(),
		request.Rules(ctx),
		validation.Messages(request.Messages(ctx)),
	)
	if err != nil {
		return r.response.Error(ctx, 500, "Internal validation error", err.Error())
	}
	if validator.Fails() {
		return r.response.Error(ctx, 422, "Validation failed", validator.Errors().All())
	}
	if err := ctx.Request().Bind(request); err != nil {
		return r.response.Error(ctx, 400, "Invalid request body", err.Error())
	}

	// Save contact ot db
	now := time.Now()
	account := models.Contact{
		Name:      request.Name,
		Email:     request.Email,
		CreatedAt: &now,
	}
	if err := facades.Orm().Query().Create(&account); err != nil {
		return r.response.Error(ctx, http.StatusInternalServerError, "Failed to create account", err.Error())
	}

	// Submit workflow temporal
	go func() {
		temporalClient := temporal.NewTemporalClient()
		defer temporalClient.Close()

		workflowOptions := client.StartWorkflowOptions{
			TaskQueue: temporal.ContactTaskQueue,
		}

		logDetails := temporal.LogActivities{
			ReferenceID:   account.ID,
			ReferenceName: "contacts",
			Action:        "created",
		}

		_, err := temporalClient.ExecuteWorkflow(ctx, workflowOptions, "ContactLoggingWorkflow", logDetails)
		if err != nil {
			fmt.Println("Failed to execute Temporal workflow:", err)
		}
	}()

	return r.response.Success(ctx, account, "Contact created successfully")
}
