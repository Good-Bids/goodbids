module.exports = {
  './**/*.{js,jsx,ts,tsx}': [
    'eslint',
    'prettier --check ./src/**/*.{js,jsx,ts,tsx}',
  ],
  './**/*.css': [
    'prettier --check ./src/**/*.css',
  ],
};
