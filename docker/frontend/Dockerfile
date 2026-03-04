FROM node:22-alpine AS builder

WORKDIR /app

# Enable pnpm via corepack (built into Node.js)
RUN corepack enable && corepack prepare pnpm@latest --activate

# Copy package files (including lock file for reproducible builds)
COPY frontend/package.json frontend/pnpm-lock.yaml* ./

# Install dependencies
RUN pnpm install --frozen-lockfile || pnpm install

# Copy source files
COPY frontend/ ./

# Build the application
RUN pnpm run build

# Production stage - serve with nginx
FROM nginx:alpine

# Copy built files
COPY --from=builder /app/build /usr/share/nginx/html

EXPOSE 80
