package rpg

class Permission {

    String id

    static constraints = {
        id bindable: true, generator: 'assigned'
    }
}
