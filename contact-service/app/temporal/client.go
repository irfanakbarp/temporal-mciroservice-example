package temporal

import (
	"log"
	"os"

	"go.temporal.io/sdk/client"
)

// Make temporal client
func NewTemporalClient() client.Client {
	c, err := client.Dial(client.Options{
		HostPort:  os.Getenv("TEMPORAL_HOST"),
		Namespace: os.Getenv("TEMPORAL_NAMESPACE"),
	})
	if err != nil {
		log.Fatalln("Unable to create Temporal client:", err)
	}
	return c
}
