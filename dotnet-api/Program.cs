using Microsoft.AspNetCore.Builder;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Hosting;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.AspNetCore.Http;

var builder = WebApplication.CreateBuilder(args);

// Configure services
builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(options =>
    {
        // JWT settings for demonstration; replace with your actual configuration.
        options.Audience = "your-audience";
        options.Authority = "https://your-auth-domain.com/";
    });
builder.Services.AddAuthorization();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

// Configure Swagger for API documentation.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseAuthentication();
app.UseAuthorization();

// Health check endpoint
app.MapGet("/api/health", () => Results.Ok("API is healthy"))
    .WithName("HealthCheck");

// Sample authentication endpoint (mocked)
app.MapPost("/api/authenticate", (UserCredentials creds) =>
{
    // In a real application, validate the user credentials.
    var token = "mock-jwt-token";
    return Results.Ok(new { token });
});

// Sample order processing endpoint
app.MapPost("/api/orders", (Order order) =>
{
    // Process order logic would go here.
    return Results.Ok(new { orderId = 12345, status = "Processed" });
});

// Sample payment processing endpoint
app.MapPost("/api/payments", (PaymentInfo payment) =>
{
    // Mock payment processing (e.g., integration with PayPal/Stripe).
    return Results.Ok(new { paymentId = 67890, status = "Paid" });
});

app.Run();

record UserCredentials(string Username, string Password);
record Order(int ProductId, int Quantity, string CustomerName, string Address);
record PaymentInfo(decimal Amount, string PaymentMethod); 