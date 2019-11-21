package rpg

class Stack {

    int amount

    static belongsTo = [ item: Item, slot: Slot, enchantment: Enchantment ]

    static constraints = {
        amount unsigned: true
        enchantment nullable: true
        slot nullable: true
        item nullable: false
    }
}
