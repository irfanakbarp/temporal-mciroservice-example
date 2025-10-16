package main

import (
	"log"
	"os"

	"github.com/joho/godotenv"
	"go.temporal.io/sdk/client"
	"go.temporal.io/sdk/worker"

	"goravel/app/temporal/types"
	"goravel/app/temporal/workflows"
	"goravel/app/temporal/activities"
)

func main() {
	// Load .env file
	if err := godotenv.Load(".env"); err != nil {
		log.Println("⚠️  Warning: .env file not found or cannot be loaded")
	}

	// Conenct to temporal server
	c, err := client.Dial(client.Options{
		HostPort:  os.Getenv("TEMPORAL_HOST"),
		Namespace: "default",
	})
	if err != nil {
		log.Fatalln("Unable to create Temporal client:", err)
	}
	defer c.Close()

	// Register worker
	w := worker.New(c, types.ContactTaskQueue, worker.Options{})

	// Register Workflow and Activity
	w.RegisterWorkflow(workflows.NewContactLoggingWorkflow().ContactLoggingWorkflow)
	w.RegisterActivity(activities.NewLoggingActivity().ContactLogging)

	log.Println("Temporal Worker started — waiting for tasks on:", types.ContactTaskQueue)

	if err := w.Run(worker.InterruptCh()); err != nil {
		log.Fatalln("Unable to start worker:", err)
	}
}
