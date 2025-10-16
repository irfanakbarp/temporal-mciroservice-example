package routes

import (
	"goravel/app/http/controllers"

	"github.com/goravel/framework/contracts/route"
)

func ContactServiceRoutes(router route.Router) {
	contactController := controllers.NewContactController()

	router.Prefix("/contact-svc").Group(func(contact route.Router) {
		contact.Prefix("/contacts").Group(func(contact route.Router) {
			contact.Post("", contactController.CreateContact)
		})
	})
}
