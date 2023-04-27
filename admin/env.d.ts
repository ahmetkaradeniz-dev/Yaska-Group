/// <reference path="auto-imports.d.ts" />
/// <reference path="components.d.ts" />
/// <reference types="vite/client" />
/// <reference types="vite-plugin-pages/client" />

import 'vue-router'

declare module 'vue-router' {
  interface RouteMeta {
    action?: string
    subject?: string
    layoutWrapperClasses?: string
  }
}


declare module "vue-infinite-loading"  {
  import Vue from 'vue'

  export default class InfiniteLoading extends Vue{
      distance: number;
      onInfinite: Function;
      spinner: string;
      direction: string;
      forceUseInfiniteWrapper: boolean;
  }

  export interface StateChanger {
      loaded(): void;
      complete(): void;
      reset(): void;
  }
}