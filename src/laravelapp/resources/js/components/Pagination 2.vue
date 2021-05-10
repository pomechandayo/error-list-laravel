<template>
  <ul class="pagination">
    <li class="page-item" v-if="hasPrev">
        <a class="page-link" href="#" @click.prevent="move(data.current_page-1)">前へ</a>    
    </li>
    <li :class="getPageClass(page)" v-for="page in pages">
        <a class="page-link" href="#" v-text="page" @click.prevent="move(page)"></a>
    </li>
    <li class="page-item" v-if="hasNext">
        <a class="page-link" href="#" @click.prevent="move(data.current_page+1)">次へ</a>
    </li>
  </ul>
</template>
<script>
  export default {
    name: 'Pagination',
    props: {
      data: {}
    },
    methods: {
      move(page) {
        if(!this.isCurrentPage(page)) {
          this.$emit('move-page',page);
        }
      },
      isCurrentPage(page) {
        return (this.data.current_page === page);
      },
      getPageClass(page) {
        let classes = ['page-item'];

        if(this.isCurrentPage(page)) {
          classes.push('active');
        }
        return classes;
      }
    },
    computed: {
      hasPrev() {
        return (this.data.prev_page_url !== null);
      },
      hasNext() {
        return (this.data.next_page_url !== null);
      },
      pages() {
        let pages = [];

        for(let i = 1; i <= this.data.last_page; i++){
          pages.push(i);
        }
        return pages;
      }
    },
  }
</script>