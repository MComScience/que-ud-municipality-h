var app = new Vue({
  el: "#app",
  data: {
    serviceId: null,
    isCard: false
  },
  methods: {
    onCard: function() {
      this.isCard = true;
      Swal.fire({
        type: "warning",
        title: "กรุณาเสียบบัตรประชาชน...",
        html: `<img src="/imgs/business-card.png" class="img-responsive center-block" />`,
        showCancelButton: true,
        showConfirmButton: false,
        animation: false,
        allowOutsideClick: false,
        cancelButtonText: "ยกเลิก",
        heightAuto: false
      }).then(result => {
        if (result.value) {
        } else {
          app.serviceId = null;
        }
      });
    },
    onNoCard: function(serviceId) {
      this.serviceId = serviceId;
      this.isCard = false;
      // Swal.fire({
      //   // type: "info",
      //   // title: "รอสักครู่...",
      //   html: `<img src="/imgs/background3.jpg" class="img-responsive center-block" />`,
      //   showCancelButton: false,
      //   showConfirmButton: false,
      //   grow: "fullscreen",
      //   animation: false,
      //   allowOutsideClick: false,
      //   heightAuto: false,
      //   timer: 200000
      // });
      // setTimeout(() => {
      //   Swal.fire({
      //     imageUrl: "/imgs/background3.jpg",
      //     imageWidth: "100%",
      //     imageHeight: 650,
      //     width: "100%",
      //     showCancelButton: false,
      //     showConfirmButton: false,
      //     timer: 5000,
      //     heightAuto: false,
      //     padding: "1em"
      //   });
      // }, 3000);
      $.ajax({
        url: baseUrl + "/app/kiosknocard/register-nocard",
        type: "POST",
        data: {
          service_id: app.serviceId
        },
        dataType: "json",
        success: function(res) {
          if (res.success === true) {
            socket.emit("register", res);
            window.open(res.url, "myPrint", "width=50, height=50");
            setTimeout(() => {
              Swal.fire({
                // type: "success",
                // title: "กรุณารับบัตรคิว",
                imageWidth: "100%",
                imageHeight: 650,
                width: "90%",
                imageUrl: '/imgs/bg_new.jpg', ///imgs/ticket.png
                showConfirmButton: false,
                showCancelButton: false,
                timer: 5000,
                heightAuto: false,
                // padding: "1em"
              });
            },5000);
          } else {
            swal("Oops...", JSON.stringify(res.message), "error");
          }
          app.clearData();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          app.clearData();
          swal({
            type: "error",
            title: textStatus,
            text: errorThrown
          });
        }
      });
    },
    serviceConfirm: function(serviceId, serviceName) {
      this.serviceId = serviceId;
      Swal.fire({
        title: "มีบัตรประชาชนหรือไม่?",
        text: serviceName,
        type: "question",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        heightAuto: false,
        //confirmButtonText: 'มีบัตร',
        //cancelButtonText: 'ไม่มีบัตร',
        footer: `
                <a class="btn btn-lg btn-block btn-warning" onclick="onNoCard()"><i class="fa fa-hand-pointer-o"></i>ตกลง</a>
                <a class="btn btn-lg btn-block btn-danger" onclick="onCancel()"><i class="fa fa-close"></i> ยกเลิก</a>`
      });
    },
    clearData: function() {
      this.serviceId = null;
      this.isCard = false;
    }
  }
});

onCancel = () => {
  Swal.close();
  app.serviceId = null;
};

onCard = () => {
  app.onCard();
};

onNoCard = () => {
  app.onNoCard();
};

$(function() {
  socket
    .on("card-inserted", function(res) {
      // เสียบบัตร
      if (
        app.serviceId !== null &&
        app.isCard &&
        res.com_name === device.device_name
      ) {
        Swal.fire({
          type: "warning",
          title: "กำลังอ่านบัตร...",
          html: `<div class="loader">Loading...</div>`,
          showCancelButton: false,
          showConfirmButton: false,
          animation: false,
          allowOutsideClick: false,
          heightAuto: false
        });
      }
    })
    .on("read-smart-card", function(res) {
      // อ่านบัตรเสร็จ
      if (
        app.serviceId !== null &&
        app.isCard &&
        res.com_name === device.device_name
      ) {
        $.ajax({
          url: baseUrl + "/app/kiosk/decode-data",
          type: "POST",
          data: res,
          dataType: "json",
          error: function(jqXHR, textStatus, errorThrown) {
            swal({
              type: "error",
              title: textStatus,
              text: errorThrown,
              heightAuto: false
            });
          },
          success: function(response) {
            if (response.success === true) {
              $.ajax({
                url: baseUrl + "/app/kiosk/register",
                type: "POST",
                data: {
                  profile: response.personal,
                  service_id: app.serviceId
                },
                dataType: "json",
                success: function(res) {
                  if (res.success === true) {
                    socket.emit("register", res);
                    window.open(res.url, "myPrint", "width=800, height=600");
                    Swal.fire({
                      type: "success",
                      title: "กรุณารับบัตรคิว",
                      html: `<img src="/imgs/ticket.png" class="img-responsive center-block" />`,
                      showConfirmButton: false,
                      showCancelButton: false,
                      timer: 2000,
                      heightAuto: false
                    });
                  } else {
                    swal("Oops...", res.message, "error");
                  }
                  app.clearData();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  app.clearData();
                  swal({
                    type: "error",
                    title: textStatus,
                    text: errorThrown,
                    heightAuto: false
                  });
                }
              });
            } else {
              app.clearData();
              swal("Oops...", res.message, "error");
            }
          }
        });
      }
    })
    .on("READING_FAIL", function(res) {
      if (
        app.serviceId !== null &&
        app.isCard &&
        res.com_name === device.device_name
      ) {
        app.clearData();
        swal("Oops...", "เกิดข้อผิดพลาด", "error");
      }
    });
});
