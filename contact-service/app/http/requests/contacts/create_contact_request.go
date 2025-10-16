package contacts

import (
	"github.com/goravel/framework/contracts/http"
	"github.com/goravel/framework/contracts/validation"
)

type CreateContactRequest struct {
	Name  string `form:"name" json:"name"`
	Email string `form:"email" json:"email"`
}

func (r *CreateContactRequest) Authorize(ctx http.Context) error {
	return nil
}

func (r *CreateContactRequest) Filters(ctx http.Context) map[string]string {
	return map[string]string{}
}

func (r *CreateContactRequest) Rules(ctx http.Context) map[string]string {
	return map[string]string{
		"name": "required|min_len:3|max_len:255",
		"email": "email",
	}
}

func (r *CreateContactRequest) Messages(ctx http.Context) map[string]string {
	return map[string]string{
		"name.required": "Account name is required",
	}
}

func (r *CreateContactRequest) Attributes(ctx http.Context) map[string]string {
	return map[string]string{}
}

func (r *CreateContactRequest) PrepareForValidation(ctx http.Context, data validation.Data) error {
	return nil
}
