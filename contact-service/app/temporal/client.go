package temporal

import (
	"log"

	"go.temporal.io/sdk/client"
)

// Make temporal client
func NewTemporalClient() client.Client {
	c, err := client.NewClient(client.Options{
		HostPort:  "localhost:7233",
		Namespace: "default",
	})
	if err != nil {
		log.Fatalln("Unable to create Temporal client:", err)
	}
	return c
}
