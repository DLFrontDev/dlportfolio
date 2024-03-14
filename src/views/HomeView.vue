<template>
  <InfoContainer class="home">
    <h1>{{ t("cv.title") }}</h1>

    <h2>{{ t("cv.skills") }}</h2>
    <div class="skills-container">
      <template v-for="skill in skills">
        <div class="title">{{ skill.title }}</div>
        <div class="levels-container">
          <div
            class="level"
            :class="{ active: n <= skill.level }"
            v-for="n in 5"
          />
        </div>
      </template>
    </div>

    <div class="extra-info">
      <div class="about-container">
        <h2>{{ t("cv.about") }}</h2>
        <ul>
          <li v-for="point in keypoints">{{ point }}</li>
        </ul>
      </div>
      <div class="hobby-container">
        <h2>{{ t("cv.hobbies") }}</h2>
        <p v-for="hobby in hobbies">{{ hobby }}</p>
      </div>
    </div>
  </InfoContainer>
</template>

<script lang="ts" setup>
import { useI18n } from "vue-i18n";
import InfoContainer from "@/components/InfoContainer.vue";
import { computed } from "vue";

const { t } = useI18n();

const skills: {
  title: string;
  level: number;
}[] = [
  {
    title: "HTML",
    level: 5,
  },
  {
    title: "CSS",
    level: 5,
  },
  {
    title: "SCSS",
    level: 5,
  },
  {
    title: "Javascript",
    level: 5,
  },
  {
    title: "Vue",
    level: 5,
  },
  {
    title: "Nuxt",
    level: 4,
  },
  {
    title: "React",
    level: 4,
  },
  {
    title: "Svelte",
    level: 1,
  },
  {
    title: "Angular",
    level: 1,
  },
  {
    title: "Storyblok",
    level: 4,
  },
  {
    title: "PHP",
    level: 2,
  },
  {
    title: "MYSQL",
    level: 1,
  },
];

const keypoints = computed(() => [
  t("cv.keypoint.organized"),
  t("cv.keypoint.integration"),
  t("cv.keypoint.antecipation"),
  t("cv.keypoint.synergy"),
  t("cv.keypoint.focus"),
]);

const hobbies = computed(() => [
  t("cv.hobby.videogames"),
  t("cv.hobby.writing"),
  t("cv.hobby.tabletop"),
]);
</script>

<style lang="scss" scoped>
.home {
  .skills-container {
    margin: 0 auto 50px;
    display: grid;
    grid-template-columns: min-content 1fr;
    align-items: center;
    gap: 30px 20px;

    @media screen and (min-width: $break-sm-min) {
      grid-template-columns: repeat(2, min-content 120px);
    }

    @media screen and (min-width: $break-md-min) {
      grid-template-columns: repeat(3, min-content 120px);
    }

    .title {
      font-size: 1.1em;
      font-weight: 700;
    }

    .levels-container {
      $hexagon-width: 20px;
      $hexagon-height: $hexagon-width * 0.87;

      display: flex;
      padding-top: $hexagon-height / 2 + 2px;
      padding-left: $hexagon-width / 4;

      .level {
        position: relative;
        width: $hexagon-width / 2;
        height: $hexagon-height;
        margin-left: $hexagon-width / 4 - 3px;
        margin-right: $hexagon-width / 4;
        background: $color-grey;

        &:before,
        &:after {
          position: absolute;
          display: block;
          height: 100%;
          top: 0;
          content: "";
          border-top: ($hexagon-height / 2) solid transparent;
          border-bottom: ($hexagon-height / 2) solid transparent;
          box-sizing: border-box;
        }

        &:before {
          right: 100%;
          border-right: ($hexagon-width / 4) solid $color-grey;
        }

        &:after {
          left: 100%;
          border-left: ($hexagon-width / 4) solid $color-grey;
        }

        &:nth-child(2n) {
          margin-top: $hexagon-width / -2;
        }

        &.active {
          background-color: $color-blue;

          &:before {
            border-right-color: $color-blue;
          }

          &:after {
            border-left-color: $color-blue;
          }
        }
      }
    }
  }

  .extra-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);

    ul {
      padding-left: 15px;

      li {
        margin-bottom: 10px;
      }
    }
  }
}
</style>
