$(document).ready(() => {

  const currentPath = window.location.pathname.split("/").pop()

  $(".nav-link, .btn-toggle-nav a").each(function () {
    const link = $(this).attr("href")

    if (link === currentPath) {
      $(this).addClass("active")

      $(this).closest('.collapse').addClass('show')
      $(this).closest('li').find('button').removeClass('collapsed').attr('aria-expanded', 'true')
    }
  })

})

