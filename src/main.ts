import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import { VueFire, VueFireAuth } from "vuefire";
import { firebaseApp } from "./firebase";
import { createI18n } from "vue-i18n";

import pt from "./lang/pt";
import en from "./lang/en";

const app = createApp(App);

app.use(VueFire, {
  firebaseApp,
  modules: [VueFireAuth()],
});

app.use(
  createI18n({
    legacy: false,
    locale:
      navigator.languages.includes("pt") || navigator.language == "pt"
        ? "pt"
        : "en",
    fallbackLocale: "en",
    messages: {
      pt,
      en,
    },
  })
);

app.use(router);

app.mount("#app");
