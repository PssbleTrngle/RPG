package rpg

class UrlMappings {

    static mappings = {
        get "/$controller(.$format)?"(action: 'index')
        get "/$controller/$id(.$format)?"(action: 'show')
        post "/$controller/$action(.$format)?"()

        "/"(controller: 'application', action: 'index')
        "500"(view: '/assertion', exception: AssertionError)
        "500"(view: '/error')
        "404"(view: '/notFound')
    }
}
