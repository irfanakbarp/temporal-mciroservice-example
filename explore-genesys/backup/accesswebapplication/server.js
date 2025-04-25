// server.js
const { createServer: createHttpsServer } = require('https');
const { createServer: createHttpServer } = require('http');
const { parse } = require('url');
const next = require('next');
const fs = require('fs');
const express = require('express');

const dev = process.env.NODE_ENV !== 'production';
const app = next({ dev });
const handle = app.getRequestHandler();

// const httpPort = 3001;
// const httpsPort = 3000;
const httpPort = 3000;
const httpsPort = 443;

const sslOptions = {
    key: fs.readFileSync('https-requirements/localhost.key'),
    cert: fs.readFileSync('https-requirements/localhost.crt'),
    ca: fs.readFileSync('https-requirements/ca.crt'),
    requestCert: true,
    rejectUnauthorized: false
};

app.prepare().then(() => {
    // Create Express app for additional middleware if needed
    const expressApp = express();

    // Serve static files
    expressApp.use(express.static(__dirname + "/public"));

    // Handle all Next.js requests
    expressApp.use((req, res) => {
        return handle(req, res);
    });

    // Create HTTP server
    const httpServer = createHttpServer(expressApp);

    // Create HTTPS server
    const httpsServer = createHttpsServer(sslOptions, expressApp);

    // Start both servers
    httpServer.listen(httpPort, (err) => {
        if (err) throw err;
        console.log(`> HTTP Server running on http://localhost:${httpPort}`);
    });

    httpsServer.listen(httpsPort, (err) => {
        if (err) throw err;
        console.log(`> HTTPS Server running on https://localhost:${httpsPort}`);
    });
});