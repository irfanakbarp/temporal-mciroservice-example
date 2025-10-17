package workflows

import (
	"time"
	"go.temporal.io/sdk/temporal"
	"go.temporal.io/sdk/workflow"
	"goravel/app/temporal/activities"
	"goravel/app/temporal/types"
)

type ContactLoggingWorkflow struct{}

func NewContactLoggingWorkflow() *ContactLoggingWorkflow {
	return &ContactLoggingWorkflow{}
}

func (w *ContactLoggingWorkflow) ContactLoggingWorkflow(ctx workflow.Context, log types.LogActivities) error {
	ao := workflow.ActivityOptions{
		StartToCloseTimeout: time.Second * 30,
		RetryPolicy: &temporal.RetryPolicy{
			InitialInterval:    10 * time.Second,
			BackoffCoefficient: 1.0,
			MaximumInterval:    10 * time.Second,
			MaximumAttempts:    10,
		},
	}

	// Apply to workflow context
	ctx = workflow.WithActivityOptions(ctx, ao)

	// Run activity 
	act := activities.NewLoggingActivity()
	err := workflow.ExecuteActivity(ctx, act.ContactLoggingActivity, log).Get(ctx, nil)
	if err != nil {
		// bisa juga workflow logger
		workflow.GetLogger(ctx).Error("Activity failed", "error", err)
		return err
	}

	return nil
}
