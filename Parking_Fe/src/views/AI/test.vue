// Component Vue.js
<template>
  <div>
    <div class="camera-container">
      <video ref="video" @loadedmetadata="onVideoLoaded" autoplay></video>
      <button @click="captureImage">Quét biển số</button>
    </div>
    
    <div v-if="processing" class="loading">Đang xử lý...</div>
    
    <div v-if="licensePlate" class="result">
      <h3>Kết quả nhận dạng:</h3>
      <p>Biển số: {{ licensePlate }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      stream: null,
      processing: false,
      licensePlate: null
    }
  },
  mounted() {
    this.startCamera();
  },
  beforeUnmount() {
    this.stopCamera();
  },
  methods: {
    async startCamera() {
      try {
        this.stream = await navigator.mediaDevices.getUserMedia({ video: true });
        this.$refs.video.srcObject = this.stream;
      } catch (error) {
        console.error('Không thể truy cập camera:', error);
      }
    },
    stopCamera() {
      if (this.stream) {
        this.stream.getTracks().forEach(track => track.stop());
      }
    },
    onVideoLoaded() {
      console.log('Camera đã sẵn sàng');
    },
    captureImage() {
      this.processing = true;
      
      // Tạo canvas để chụp ảnh từ video
      const canvas = document.createElement('canvas');
      canvas.width = this.$refs.video.videoWidth;
      canvas.height = this.$refs.video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(this.$refs.video, 0, 0, canvas.width, canvas.height);
      
      // Chuyển đổi canvas thành file
      canvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('image', blob, 'license_plate.jpg');
        
        // Gửi hình ảnh đến API Laravel
        axios.post('/api/license-plate-recognition', formData)
          .then(response => {
            // Xử lý kết quả
            if (response.data.result && response.data.result.license_plates.length > 0) {
              // Nếu có OCR, bạn có thể lấy text ở đây
              this.licensePlate = "Đã phát hiện biển số";
              
              // Lưu thông tin biển số vào hệ thống quản lý bãi đỗ xe
              this.saveLicensePlateToSystem(response.data.result);
            } else {
              this.licensePlate = "Không phát hiện biển số";
            }
          })
          .catch(error => {
            console.error('Lỗi khi xử lý biển số:', error);
          })
          .finally(() => {
            this.processing = false;
          });
      }, 'image/jpeg', 0.9);
    },
    saveLicensePlateToSystem(result) {
      // Gọi API để lưu thông tin biển số vào hệ thống quản lý
      // Ví dụ: ghi nhận xe vào/ra bãi đỗ
    }
  }
}
</script>
