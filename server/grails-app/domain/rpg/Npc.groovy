package rpg

class Npc {

    String id
    int level, maxHealth

    static belongsTo = [ rank: Rank ]
    static hasMany = [ loot: Item, skills: Skill ]

    static constraints = {
        id blank: false, nullable: false, unique: true
        level unsigned: true
        maxHealth unsigned: true
    }
}
