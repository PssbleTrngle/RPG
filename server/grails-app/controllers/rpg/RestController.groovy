package rpg


import grails.rest.*
import grails.converters.*

class RestController<T> extends RestfulController<T> {
	static responseFormats = ['json', 'xml']

    RestController(Class<T> domainClass) {
        this(domainClass, false)
    }

    RestController(Class<T> domainClass, boolean readOnly) {
        super(domainClass, readOnly)
    }

}
