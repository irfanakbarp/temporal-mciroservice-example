package temporal

import (
	"bytes"
	"encoding/json"
	"fmt"
	"net/http"
	"os"
)

type ContactActivity struct {
	BaseURL string
}

func NewContactActivity() *ContactActivity {
	base := os.Getenv("LOGGING_SVC_URL")
	if base == "" {
		panic("LOGGING_SVC_URL is missing in environment")
	}
	return &ContactActivity{BaseURL: base}
}

func (a *ContactActivity) CreateContactLogging(log LogActivities) error {
	body, err := json.Marshal(log)
	if err != nil {
		return err
	}

	//
	url := a.BaseURL + "/logs"

	resp, err := http.Post(url, "application/json", bytes.NewBuffer(body))
	if err != nil {
		return fmt.Errorf("failed to call log service: %w", err)
	}
	defer resp.Body.Close()

	if resp.StatusCode >= 300 {
		return fmt.Errorf("log service returned: %s", resp.Status)
	}

	return nil
}
