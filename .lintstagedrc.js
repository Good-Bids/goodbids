module.exports = {
  './client-mu-plugins/goodbids/assets/**/*.{js,jsx,ts,tsx}': [
    'eslint',
    'prettier --check ./client-mu-plugins/goodbids/src/**/*.{js,jsx,ts,tsx}',
  ],
  './client-mu-plugins/goodbids/assets/**/*.css': [
    'prettier --check ./client-mu-plugins/goodbids/src/**/*.css',
  ],
};
