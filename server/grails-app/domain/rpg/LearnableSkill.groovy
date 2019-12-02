package rpg

class LearnableSkill {

    int level

    static belongsTo = [ skill: Skill, clazz: Clazz, teacher: Npc ]

    static constraints = {
        skill nullable: false
        clazz nullable: false
        teacher nullable: true
    }
}
