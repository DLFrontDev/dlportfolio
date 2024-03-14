<template>
  <div class="info-container">
    <slot />
  </div>
</template>

<style lang="scss" scoped>
.info-container {
  padding: 40px 30px;

  @media screen and (min-width: $break-lg-min) {
    padding: 40px 50px;
  }

  :deep(h1) {
    margin-top: 0;
    margin-bottom: 30px;

    @media screen and (min-width: $break-lg-min) {
      margin-bottom: 50px;
    }
  }
}
</style>
