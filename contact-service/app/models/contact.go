package models

import (
	"time"

	"github.com/google/uuid"
	"github.com/goravel/framework/database/orm"
)

type Contact struct {
	orm.Model
	ID               uuid.UUID  `gorm:"type:uuid;default:gen_random_uuid()" json:"id"`
	Name  string
	Email string

	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}
