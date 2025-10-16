package types

import "github.com/google/uuid"

const ContactTaskQueue = "CONTACT_TASK_QUEUE"

type LogActivities struct {
	ReferenceID   uuid.UUID `form:"reference_id" json:"reference_id"`
	ReferenceName string    `form:"reference_name" json:"reference_name"`
	Action        string    `form:"action" json:"action"`
}
