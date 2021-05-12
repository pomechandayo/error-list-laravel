<template>
<div>


  <div 
  v-model="success"
  class="edit-profile-message"
  >
    {{ success }}
  </div>

  <div class="edit-profile-box">
    
    <h2 class="edit-profile-h2">プロフィール編集</h2>
    <div class="edit-profile-border"></div>
    
    <form 
    action="/mypage/edit" 
    method="post" 
    enctype="multipart/form-data"
    >
   <input type="hidden" name="_token" :value="csrf" />

    <!-- プロフィール画像 -->
    <label for="profile_image" class="edit-profile-label1">
      <input 

        type="file" 
        name="profile_image" 
        accept="image/png,image/jpeg,image/gif" class="edit-profile-input" id="profile_image" style="display: none;;" />
      
          <img :src="profile_image"  class="profile_img">
        
     </label>
    <label for="name" class="edit-profile-label2">ニックネーム</label>
    <input 
      type="text" 
      class="edit-profile-name" 
      name="name"
      :value="user_name"
      >
    
      <li class="error-message"></li>
   

    <button 
    type="submit" 
    class="edit-profile-save"
    >
      変更
    </button>

    </form>
  </div>

</div>
</template>
<script>

export default{
  data() {
    return {
      csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),
      
      user_name:    [],
      profile_image:[],
      success:      '',
    };
  },
   props: {
    auth:{
     type: Object|Array
    },
  },
   methods: {
    getProfileImage() {
      const data = {
        userid: this.auth.id
      }
     const self = this;
     const url ='/api/profile/' + this.auth.id;

     this.$http.get(url)
      .then(response => {
        self.user_name = response.data.name;
        self.profile_image = response.data.profile_image;
      }).catch( error => {console.log(error)});
    },
   },
    mounted() {
      this.getProfileImage();
    },
}

</script>