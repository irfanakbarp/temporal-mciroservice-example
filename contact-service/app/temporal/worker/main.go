package main

import (
	"goravel/app/temporal"
	"log"

	"go.temporal.io/sdk/client"
	"go.temporal.io/sdk/worker"
)

func main() {
	c, err := client.Dial(client.Options{})
	if err != nil {
		log.Fatalln("Unable to create Temporal client.", err)
	}
	defer c.Close()

	w := worker.New(c, temporal.ContactTaskQueue, worker.Options{})

	w.RegisterWorkflow(temporal.NewContactWorkflow().ContactWorkflow)
	w.RegisterActivity(temporal.NewContactActivity().CreateContactLogging)

	err = w.Run(worker.InterruptCh())
	if err != nil {
		log.Fatalln("Unable to start worker", err)
	}
}
