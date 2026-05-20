<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: "1.0.0",
        title: "Freelancr API",
        description: "API dokumentasi untuk platform Freelancr - Marketplace jasa freelance berbasis kampus",
    ),
]
#[
    OA\Server(
        url: "http://localhost:8000",
        description: "Local Development Server",
    ),
]
#[
    OA\SecurityScheme(
        securityScheme: "bearerAuth",
        type: "http",
        scheme: "bearer",
        bearerFormat: "JWT",
    ),
]
class SwaggerController extends Controller {}
