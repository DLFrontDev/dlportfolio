<template>
  <div class="app-container">
    <nav class="sidebar">
      <div class="sidebar-wrapper">
        <img src="@/assets/images/logo.svg" />
        <div class="link-container">
          <router-link to="/">{{ t("navbar.cv") }}</router-link>
          <router-link to="/timeline">{{ t("navbar.timeline") }}</router-link>
        </div>
        <div class="lang-switcher">
          <button
            @click="$i18n.locale = locale"
            :class="{ active: $i18n.locale == locale }"
            v-for="locale in locales"
          >
            {{ locale }}
          </button>
        </div>
      </div>
    </nav>

    <div class="view-container">
      <RouterView v-slot="{ Component }">
        <Transition name="fade" mode="out-in">
          <component :is="Component" />
        </Transition>
      </RouterView>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const locales = ["pt", "en"];
</script>

<style lang="scss">
body {
  margin: 0;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: $color-text;
}
</style>

<style lang="scss" scoped>
.app-container {
  min-height: 100vh;
  display: grid;

  @media screen and (min-width: $break-lg-min) {
    grid-template-columns: 250px 1fr;
  }
}

.sidebar {
  position: sticky;
  top: 0;
  z-index: 2;
  border-bottom: 1px solid $color-grey;

  @media screen and (min-width: $break-lg-min) {
    display: block;
    position: static;
    background: $color-purple;
    border-right: 1px solid $color-grey;
    background-image: url("@/assets/images/sidebar_bg.svg");
  }

  .sidebar-wrapper {
    display: grid;
    grid-template-columns: 60px 1fr auto;
    gap: 20px;
    background: $color-dark-white;
    padding: 15px 30px;
    align-items: center;

    @media screen and (min-width: $break-lg-min) {
      position: sticky;
      top: 0;
      display: block;
      padding: 30px;
    }
  }

  img {
    grid-area: 1 / 1 / 2 / 2;
    max-width: 60px;

    @media screen and (min-width: $break-lg-min) {
      max-width: 100%;
    }
  }

  .lang-switcher {
    grid-area: 1 / 3 / 2 / 4;
    display: flex;
    justify-content: center;
    gap: 10px;

    @media screen and (min-width: $break-lg-min) {
      margin-top: 30px;
    }

    button {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      font-weight: 700;
      transition: all 0.2s ease-in-out;
      color: $color-dark-grey;
      text-transform: uppercase;
      font-size: 1em;

      &.active {
        color: $color-black;
      }

      &:hover {
        color: $color-blue;
      }

      + button {
        border-left: 2px solid $color-grey;
        padding-left: 10px;
      }
    }
  }

  .link-container {
    grid-area: 1 / 2 / 2 / 3;
    display: flex;
    gap: 16px;
    font-size: 1.3em;

    @media screen and (min-width: $break-lg-min) {
      flex-direction: column;
      margin-top: 30px;
    }

    a {
      text-align: center;
      text-decoration: none;
      color: inherit;
      color: $color-dark-grey;
      transition: color 0.3s ease-in-out;

      &.router-link-active,
      &:hover {
        color: $color-blue;
      }

      &.router-link-active {
        font-weight: 700;
      }
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s linear;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}
</style>
