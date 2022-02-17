// obtener la paginacion
function getPage(totalpage, currentpage) {
  var pagelist = "";
  if (totalpage > 1) {
    currentpage = parseInt(currentpage);
    pagelist += `<ul class="pagination justify-content-center">`;
    const disabledPage = currentpage == 1 ? "disabled" : "";
    pagelist += `<li class="page-item ${disabledPage}"><a class="page-link" href="#" 
       data-page="${currentpage - 1}">Return</a></li>`;

    for (let p = 1; p <= totalpage; p++) {
      const activePage = currentpage == p ? "active" : "";
      pagelist += `<li class="page-item ${activePage}"><a class="page-link" href="#" 
           data-page="${p}">${p}</a></li>`;
    }
    const nextPage = currentpage == totalpage ? "disabled" : "";
    pagelist += `<li class="page-item ${nextPage}"><a class="page-link" href="#" 
       data-page="${currentpage + 1}">Next page</a></li>`;
    pagelist += `</ul>`;
  }

  $("#pagination").html(pagelist);
}

// obtener fila de usuarios
function getuserrow(user) {
  var userRow = "";
  if (user) {
    const userphoto = user.photo
      ? user.photo
      : "https://images.pexels.com/photos/9402950/pexels-photo-9402950.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500";
    userRow = `<tr><td class="align-middle"><img width='100px' height='100px' src="uploads/${userphoto}" class="img-thumbnail rounded float-left"></td>
    <td class="align-middle">${user.name}</td>
    <td class="align-middle">${user.procedency}</td>
    <td class="align-middle">${user.age}</td>
    <td class="align-middle">
    <a href="#" class="btn btn-success mr-3 profile" data-toggle="modal" 
    data-target="#userViewModal" title="Prfile" data-id="${user.id}"><i class="fa fa-address-card-o" aria-hidden="true"></i></a>
    <a href="#" class="btn btn-secondary mr-3 edituser" data-toggle="modal" 
    data-target="#userModal" title="Edit" data-id="${user.id}"><i class="fa fa-pencil-square-o fa-lg"></i></a>
    <a href="#" class="btn btn-warning deleteuser" data-userid="14" title="Delete" data-id="${user.id}"><i class="fa fa-trash-o fa-lg"></i></a>
    </td></tr>`;
  }
  return userRow;
}

// obtener lista de usuarios
function getUsers() {
    // page === undefined ? pageat = $("#currentpage").val() : pageat = page;
  var pageat = $("#currentpage").val();
  $.ajax({
    url: "/project-jquery/ajax.php",
    type: "GET",
    dataType: "json",
    data: { page: pageat, action: "getusers" },
    beforeSend: function () {
      $("#overlay").fadeIn();
    },
    success: function (rows) {
      if (rows.users) {
        var listuser = "";
        $.each(rows.users, function (index, user) {
          listuser += getuserrow(user);
        });
        $("#userstable tbody").html(listuser);
        let totalUser = rows.counter;
        let totalpage = Math.ceil(parseInt(totalUser) / 4);
        const currentpage = $("#currentpage").val();
        getPage(totalpage, currentpage);
        $("#overlay").fadeOut();
      }
    },
    error: function (x,y,z) {
      console.log(x,y,z);
    },
  });
}

$(document).ready(function () {
  //agregar y editar el usuario

  $(document).on("submit", "#addform", function (e) {
    e.preventDefault();
    $('#searchinput').val('');
    var alerting = ($('#userid').val().length > 0) ? 'user was added succesfully' : 
    'the user has been added exited!';
    $.ajax({
      url: "/project-jquery/ajax.php",
      type: "POST",
      datatype: "json",
      data: new FormData(this),
      processData: false,
      contentType: false,
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (response) {
        console.log(response);
        $("#userModal").modal("hide");
        $("#addform")[0].reset();
        $(".message").html(alerting).fadeIn().delay(2000).fadeOut();
        getUsers();
        $("#overlay").fadeOut();
      },
      error: function () {
        alert("ERROR, system wrong.");
      },
    });
  });

  // pagination
  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    var $this = $(this);
    const pagenum = $this.data("page");
    $("#currentpage").val(pagenum);
    getUsers();
    $this.parent().siblings().removeClass("active");
    $this.parent().addClass("active");
  });

  

  // resetiar el modal del formulario con un nuevo boton
  $(document).on("click", "#addBtn", function(){
       $("#addform")[0].reset();
       $("#userid").val("");
  });

  // obtener usuarios
  $(document).on("click", "a.edituser", function () {
    var tid = $(this).data("id");
    $('#searchinput').val('');
    $.ajax({
      url: "/project-jquery/ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: tid, action: "getuser" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (users) {
           if(users){
               $('#username').val(users.name);
               $('#procedency').val(users.procedency);
               $('#age').val(users.age);
               $('#userid').val(users.id);
           }
           $("#overlay").fadeOut();
      },
      error: function () {
        console.log("ERROR");
      },
    });
  });

  $('#searchinput').on("keyup",  function(e){
   //e.preventDefault();
   const searching = $(this).val();
   if(searching !== ''){
      $.ajax({
      url: "/project-jquery/ajax.php",
      type: "GET",
      //dataType: "json",
      data: { query: searching, action: "searchuser" },
      success: function (use) {
          if (use) {
            var listuser = "";
            let data = JSON.parse(use);
            $.each(data, function (index, user) {
              listuser += getuserrow(user);
            });
            $("#userstable tbody").html(listuser);
            $("#pagination").hide();
            $("#overlay").fadeOut();
      }
    },
      error: function () {
        console.log('ERROR');
      },
    });
   } else {
     getUsers();
     $("#pagination").show();
   }
});
  // cargar usuarios
  getUsers();
});

// eliminar usuarios

 $(document).on("click", "a.deleteuser", function (e) {
     e.preventDefault();
    $('#searchinput').val('');
    var tid = $(this).data("id");
    if(confirm('sure of delete this?')){
      $.ajax({
      url: "/project-jquery/ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: tid, action: "deleteuser" },
      beforeSend: function () {
        $("#overlay").fadeIn();
      },
      success: function (rest) {
          if(rest.delete == 1){
              $(".message").html('the user has been deleted success!')
              .fadeIn().delay(2000).fadeOut();
             getUsers();
            $("#overlay").fadeOut();
          }
          
      },
      error: function () {
        console.log("ERROR");
      },
    });
}
   
    getUsers();
  });


// editar el perfil del usuario   
 $(document).on("click", "a.profile", function () {

    var tid = $(this).data("id");

    $.ajax({
      url: "/project-jquery/ajax.php",
      type: "GET",
      dataType: "json",
      data: { id: tid, action: "getuser" },
      success: function (users) {
           if(users){
                const userphoto = users.photo
      ? users.photo
      : "https://images.pexels.com/photos/9402950/pexels-photo-9402950.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500";
              const perfil = ` <div class="row">
            <div class="col-sm-6 col-md-4">
                <img src="uploads/${userphoto}" width="100px" height="100px" class="rounded responsive" />
            </div>
            <div class="col-sm-6 col-md-8">
                <h4 class="text-primary">${users.name}</h4>
                <p class="text-secondary">
                <i class="fa-solid fa-location-pin" aria-hidden="true"></i> ${users.procedency}
                <br />
                <i class="far fa-images" aria-hidden="true"></i> ${users.age}
                </p>
            </div>
        </div>`
        $('#profile').html(perfil);
    }
      },
      error: function () {
        console.log("ERROR");
      },
    });
  });



// buscar el usuario 



let b;
// class Table{
//     constructor(container, URI, columns, pageSize, maxRow, ){
//         this.container = container;
//         this.pageSize = pageSize;
//         this.URI = URI
//         this.columns = [];
//         this.data;
//         this.table;
//         this.buildTable();

//     }
//     getData(){
//         return $.ajax({
//             url: this.URI,
//             success: function(resp){
//                this.data = JSON.parse(resp);
//             },
//         });

//     }
//     buildTable(){
//         let table = document.createElement('table');
//         let obj = this;
//         this.getData().done( (data) => {
//            obj.buildRows(JSON.parse(data));
//         })
//     }
//     buildRows(data){

//         data.forEach(row =>{

//             let col = Object.keys(data).length
//             let r = document.createElement('row');

//             for(let i = 0; i < col; i++){
//                 let c = document.createElement('td');
//                 c.innerHTML = row
//             }

//         })
//     }
// }

// let tbl = document.getElementById('clientTable');
// clientTable = new Table(tbl, 'getRows.php', ['name', 'procedency', 'age'] ,1);
