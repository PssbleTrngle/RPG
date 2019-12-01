package rpg

class Stats {

    enum Stat {
        wisdom, strength, agility, luck, resistance
    }

    int wisdom = 0, strength = 0, agility = 0, luck = 0, resistance = 0

    static constraints = {}

    Stats plus(Stats other) {
        Stats stats = new Stats()
        Stat.values().each({
            String s = it.name().toLowerCase()
            stats."$s" = this."$s" + other."$s"
        })
        stats
    }

}