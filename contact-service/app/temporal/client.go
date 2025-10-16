package temporal

import (
	"log"

	"go.temporal.io/sdk/client"
)

// Make temporal client
func NewTemporalClient() client.Client {
	c, err := client.Dial(client.Options{
		HostPort:  "127.0.0.1:7233",
		Namespace: "default",
	})
	if err != nil {
		log.Fatalln("Unable to create Temporal client 1:", err)
	}
	return c
}
