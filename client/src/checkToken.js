export const checkToken = async () => {
    console.log('checking');
    if (localStorage.getItem("token") && localStorage.getItem("current_user")) {
      const formData = new FormData();
      formData.append("token", localStorage.getItem("token"));
      formData.append("name", localStorage.getItem("current_user"));

      fetch("http://127.0.0.1:8000/api/checkToken", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
            if (data.message) {
            console.log(data);
            localStorage.removeItem("token");
            localStorage.removeItem("current_user");
            window.location.href = "/";
          } else {
            window.location.href = "/panel";
          }
        });
    }

} 

