
$(function(){
    $(document).on('click','.delete-btn',function(e){
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Menghapus Data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yaa, Hapus!',
            cancelButtonText: 'Batal' // ← tambahkan ini
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link
                Swal.fire(
                    'Dihapus!',
                    'Berkas Anda telah dihapus.',
                    'success' // sebaiknya pakai 'success' bukan 'berhasil'
                )
            }
        })
    });
});


  /////////// confirm 

  $(function(){
    $(document).on('click','.confirm-btn',function(e){
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Menghapus Data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yaa, Hapus!',
            cancelButtonText: 'Batal' // ← tambahkan ini
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link
                Swal.fire(
                    'Dihapus!',
                    'Berkas Anda telah dihapus.',
                    'success'
                )
            }
        })
    });
});
