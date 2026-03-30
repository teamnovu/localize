import LocalizeList from "./components/LocalizeList.vue";

Statamic.booting(() => {
  Statamic.$components.register("localize-list", LocalizeList);
});
