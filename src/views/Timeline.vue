<template>
  <InfoContainer class="timeline">
    <h1>{{ t("timeline.title") }}</h1>

    <div class="timeline-container">
      <div class="event" :class="[...event.className]" v-for="event in events">
        <div class="year-container">
          <div class="year-wrapper">
            <div class="year">{{ event.year }}</div>
            <div class="marker" />
          </div>
        </div>
        <div class="text-container">
          <h3>{{ event.title }}</h3>
          <div class="image-container">
            <img :src="require(`@/assets/images/${event.image}`)" />
          </div>
          <p>
            {{ event.description }}
          </p>
          <div class="stack-container" v-if="event.stack">
            <h4>{{ t("timeline.list.stack") }}</h4>
            <p>{{ event.stack.join(", ") }}</p>
          </div>
        </div>
      </div>
    </div>
  </InfoContainer>
</template>

<script lang="ts" setup>
import InfoContainer from "@/components/InfoContainer.vue";
import { Ref, computed } from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const events: Ref<
  {
    year: string;
    title: string;
    description: string;
    image: string;
    className: string[];
    stack?: string[];
  }[]
> = computed(() => [
  {
    year: "2022",
    title: t("timeline.event.comparaja.title"),
    description: t("timeline.event.comparaja.description"),
    image: "comparaja.svg",
    stack: ["Vue 3", "Nuxt", "SCSS", "Storyblok"],
    className: ["color-comparaja"],
  },
  {
    year: "2020",
    title: t("timeline.event.merkle.title"),
    description: t("timeline.event.merkle.description"),
    image: "merkle.jpeg",
    stack: [
      "HTML",
      "Javascript",
      "CSS",
      "CS-Cart",
      "PHP",
      "Smarty PHP Templates",
    ],
    className: ["color-prime"],
  },
  {
    year: "2019",
    title: t("timeline.event.visionbox.title"),
    description: t("timeline.event.visionbox.description"),
    image: "visionbox.png",
    stack: ["React", "SCSS", "Electron"],
    className: ["color-prime"],
  },
  {
    year: "2017",
    title: t("timeline.event.altice.title"),
    description: t("timeline.event.altice.description"),
    image: "altice.png",
    stack: ["HTML", "Javascript", "CSS"],
    className: ["color-prime"],
  },
  {
    year: "2017",
    title: t("timeline.event.primeit.title"),
    description: t("timeline.event.primeit.description"),
    image: "primeit.png",
    className: ["color-prime"],
  },
  {
    year: "2017",
    title: t("timeline.event.lsd.title"),
    description: t("timeline.event.lsd.description"),
    image: "lsd.png",
    stack: ["HTML", "Javascript", "CSS", "PHP", "MYSQL"],
    className: ["color-education"],
  },
  {
    year: "2015",
    title: t("timeline.event.uni.title"),
    description: t("timeline.event.uni.description"),
    image: "uni.jpg",
    className: ["color-education"],
  },
]);
</script>

<style lang="scss" scoped>
.timeline {
  .timeline-container {
    display: grid;
    gap: 20px;

    .event {
      position: relative;
      display: grid;
      grid-template-columns: auto 1fr;
      grid-template-rows: min-content;
      gap: 20px;

      &:not(:last-child)::after {
        content: "";
        position: absolute;
        height: calc(100% + 5px);
        width: 2px;
        top: 20px;
        left: 72px;
      }

      .year-wrapper {
        display: grid;
        grid-template-columns: 45px 16px;
        align-items: center;
        gap: 20px;

        .year {
          font-size: 1.17em;
          font-weight: 700;
        }

        .marker {
          height: 16px;
          width: 16px;
          border-radius: 50%;
        }
      }

      .text-container {
        display: grid;
        grid-template-rows: repeat(3, auto);
        gap: 15px;

        @media screen and (min-width: $break-md-min) {
          grid-template-columns: 1fr 200px;
        }

        h3 {
          margin: 0;
          grid-area: 1 / 1 / 2 / 2;

          @media screen and (min-width: $break-md-min) {
            grid-area: 1 / 1 / 2 / 3;
          }
        }

        .image-container {
          display: none;
          align-items: flex-start;
          justify-content: center;

          @media screen and (min-width: $break-md-min) {
            grid-area: 2 / 2 / 4 / 3;
            display: flex;
          }

          img {
            display: block;
            max-width: 100%;
          }
        }

        > p {
          margin: 0;
          grid-area: 2 / 1 / 3 / 2;
        }

        .stack-container {
          grid-area: 3 / 1 / 4 / 2;

          h4 {
            margin: 0;
          }

          p {
            margin: 0;
          }
        }
      }

      &.color-comparaja {
        .marker,
        &::after {
          background: $color-comparaja;
        }
      }

      &.color-prime {
        .marker,
        &::after {
          background: $color-prime;
        }
      }

      &.color-education {
        .marker,
        &::after {
          background: $color-education;
        }
      }
    }
  }
}
</style>
