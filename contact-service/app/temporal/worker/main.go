package main

import (
	"log"

	"github.com/joho/godotenv"
	"go.temporal.io/sdk/worker"

	"goravel/app/temporal/activities"
	"goravel/app/temporal/types"
	"goravel/app/temporal/workflows"
	"goravel/app/temporal"

)

func main() {
	// Load .env file
	if err := godotenv.Load(".env"); err != nil {
		log.Println(".env file not found or cannot be loaded")
	}

	// Conenct to temporal server
	c := temporal.NewTemporalClient()
	defer c.Close()

	// Register worker
	w := worker.New(c, types.ContactTaskQueue, worker.Options{})

	// Register Workflow and Activity
	w.RegisterWorkflow(workflows.NewContactLoggingWorkflow().ContactLoggingWorkflow)
	w.RegisterActivity(activities.NewLoggingActivity().ContactLoggingActivity)

	log.Println("Temporal Worker started — waiting for tasks on:", types.ContactTaskQueue)

	if err := w.Run(worker.InterruptCh()); err != nil {
		log.Fatalln("Unable to start worker:", err)
	}
}
