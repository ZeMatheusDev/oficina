<template>
    <div v-html="formattedMessage"></div>
  </template>
  
  <script>
  import { computed, defineProps } from 'vue';
  
  export default {
    props: {
      message: {
        type: String,
        required: true
      }
    },
    setup(props) {
      const formattedMessage = computed(() => {
        if (!props.message) return '';
  
        const words = props.message.split(' ');
        let line = '';
        let lines = [];
  
        for (let i = 0; i < words.length; i++) {
          const word = words[i];
          if ((line + ' ' + word).length > 80) {
            lines.push(line.trim());
            line = '';
          }
          line += (line ? ' ' : '') + word;
        }
  
        lines.push(line.trim());
  
        return lines.join('<br>');
      });
  
      return {
        formattedMessage
      };
    }
  }
  </script>