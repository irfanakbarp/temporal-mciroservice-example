package main

import (
	"log"
	"os"

	"github.com/joho/godotenv"
	"go.temporal.io/sdk/client"
	"go.temporal.io/sdk/worker"

	"goravel/app/temporal"
)

func main() {
	// 🧠 Load .env file otomatis
	if err := godotenv.Load(".env"); err != nil {
		log.Println("⚠️  Warning: .env file not found or cannot be loaded")
	}

	// Debug print biar tahu env kebaca
	log.Println("LOGGING_SVC_URL:", os.Getenv("LOGGING_SVC_URL"))

	// 🚀 Connect ke Temporal server
	c, err := client.Dial(client.Options{
		HostPort:  os.Getenv("TEMPORAL_HOST"), // misal localhost:7233
		Namespace: "default",
	})
	if err != nil {
		log.Fatalln("❌ Unable to create Temporal client:", err)
	}
	defer c.Close()

	// 🧱 Register worker
	w := worker.New(c, temporal.ContactTaskQueue, worker.Options{})

	w.RegisterWorkflow(temporal.NewContactWorkflow().ContactWorkflow)
	w.RegisterActivity(temporal.NewLoggingActivity().CreateContactLogging)

	log.Println("✅ Temporal Worker started — waiting for tasks on:", temporal.ContactTaskQueue)

	if err := w.Run(worker.InterruptCh()); err != nil {
		log.Fatalln("❌ Unable to start worker:", err)
	}
}
