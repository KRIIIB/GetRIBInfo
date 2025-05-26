<template>
<transition name="modal-animation">
  <div v-show="modalShow" class="modal">
    <transition name="modal-animation-inner">
      <div v-show="modalShow" class="modal-inner">
        <slot></slot>
        <button @click="close" type="button">Close</button>
      </div>
    </transition>
    
  </div>
</transition>
</template>

<script>
export default {
  props: ["modalShow"],
  methods: {
    close() {
      this.$emit("close");
    }
  }
}

</script>