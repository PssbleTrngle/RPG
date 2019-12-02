package rpg

class Character extends Participant {

    Date dateCreated
    String name
    int xp = 0, level = 1, skillpoints = 5

    @Override
    int maxHealth() { 100 }

    static belongsTo = [account: Account, race: Race, position: Position ]
    static hasMany = [ messages: Message, clazzes: Clazz, inventory: Stack ]

    Stats getStats() {
        Stats stats = new Stats()
        getClazzes().each({
            stats += it.getStats()
        })
        stats
    }

    static constraints = {
        name blank: false, nullable: false
        xp unsigned: true
        skillpoints unsigned: true
        level unsigned: true
        race nullable: true /* TODO remove */
    }

    static mapping = {
        sort: 'dateCreated'
    }

}
