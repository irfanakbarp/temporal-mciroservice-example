package activities

import (
	"bytes"
	"encoding/json"
	"fmt"
	"net/http"
	"os"
)

type LogActivity struct {
	BaseURL string
}

func NewLoggingActivity() *LogActivity {
	base := os.Getenv("LOGGING_SVC_URL")
	if base == "" {
		panic("LOGGING_SVC_URL is missing in environment")
	}
	return &LogActivity{BaseURL: base}
}

func (a *LogActivity) ContactLoggingActivity(data any) error {
	body, err := json.Marshal(data)
	if err != nil {
		return fmt.Errorf("failed to marshal log data: %w", err)
	}

	url := a.BaseURL + "/logs/"
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
