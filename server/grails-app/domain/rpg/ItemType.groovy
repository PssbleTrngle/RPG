package rpg

import grails.rest.Resource

@Resource(uri = '/itemtype', readOnly = true)
class ItemType implements Translated {

    String id
    boolean hasIcon = false, stackable = false

    static hasMany = [ children: ItemType ]
    static belongsTo = [ parent: ItemType ]

    Set<ItemType> ancestors() {
        def ancestors = new ArrayList<ItemType>()

        ItemType parent = this.getParent()
        if(parent) {
            ancestors.add(parent)
            ancestors.addAll(parent.ancestors())
        }

        ancestors
    }

    static constraints = {
        id bindable: true, generator: 'assigned'
        parent nullable: true
    }
}
