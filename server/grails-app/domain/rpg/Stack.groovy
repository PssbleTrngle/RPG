package rpg

class Stack {

    int amount = 1

    static belongsTo = [ item: Item, slot: Slot, enchantment: Enchantment ]

    static constraints = {
        amount unsigned: true
        enchantment nullable: true
        slot nullable: false
        item nullable: false
    }
}
