const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    projectId: "7gv4u3",
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
