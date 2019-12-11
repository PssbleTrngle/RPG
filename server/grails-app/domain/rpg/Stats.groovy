package rpg

class Stats {

    enum Stat {
        wisdom, strength, agility, luck, resistance

        @Override
        String toString() {
            this.name().toLowerCase()
        }
    }

    int wisdom = 0, strength = 0, agility = 0, luck = 0, resistance = 0

    static constraints = {}

    int get(Stat stat) {
        return this."$stat"
    }

    int total() {
        (int) Stat.values()
                .collect({ this.get(it) })
                .sum()
    }

    Stats plus(Stats other) {
        Stats stats = new Stats()
        Stat.values().each({
            stats."$it" = this.get(it) + other.get(it)
        })
        stats
    }

}