module.exports = {
  apps: [
    {
      name: 'talaja-api',
      script: 'artisan',
      args: 'serve --host=127.0.0.1 --port=8001',
      interpreter: 'php',
      cwd: __dirname,
      watch: false,
      autorestart: true,
      max_restarts: 10,
      env: {
        APP_ENV: 'local',
      },
    },
    {
      name: 'talaja-vite',
      script: 'node_modules/vite/bin/vite.js',
      args: '--host',
      cwd: __dirname,
      watch: false,
      autorestart: true,
      max_restarts: 10,
      env: {
        NODE_ENV: 'development',
      },
    },
  ],
};
