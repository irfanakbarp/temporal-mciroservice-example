package temporal

import (
	"time"

	"go.temporal.io/sdk/temporal"
	"go.temporal.io/sdk/workflow"
)

type ContactLoggingWorkflow struct{}

func NewContactLoggingWorkflow() *ContactLoggingWorkflow {
	return &ContactLoggingWorkflow{}
}

func (w *ContactLoggingWorkflow) ContactLoggingWorkflow(ctx workflow.Context, log LogActivities) error {
	ao := workflow.ActivityOptions{
		StartToCloseTimeout: time.Second * 30,
		RetryPolicy: &temporal.RetryPolicy{
			InitialInterval:    10 * time.Second,
			BackoffCoefficient: 1.0,
			MaximumInterval:    10 * time.Second,
			MaximumAttempts:    10,
		},
	}

	// Apply ke context workflow
	ctx = workflow.WithActivityOptions(ctx, ao)

	// 🔁 Jalankan activity (misal nge-log ke log-service)
	act := NewLoggingActivity()
	err := workflow.ExecuteActivity(ctx, act.ContactLogging, log).Get(ctx, nil)
	if err != nil {
		// bisa juga workflow logger
		workflow.GetLogger(ctx).Error("Activity gagal", "error", err)
		return err
	}

	return nil
}
