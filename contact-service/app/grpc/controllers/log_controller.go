package controllers

// import (
// 	"context"

// 	"github.com/goravel/framework/contracts/grpc"
// )

// type LogController struct{}

// func NewLogController() *LogController {
// 	return &LogController{}
// }

// func (r *LogController) Create(ctx context.Context, req *grpc.Request) (*grpc.Response, error) {
// 	name := req.Input("name").String()
// 	message := req.Input("message").String()

// 	// Simulasi: simpan log ke DB / console
// 	println("✅ Received log:", name, "-", message)

// 	return grpc.Success("Log created successfully"), nil
// }
