package rpg

class Battle {

    int round = 0

    static belongsTo = [ active: Character, position: Position ]
    static hasMany = [ fields: Field, messages: Message ]

    Set<Participant> getParticipants() {
        getFields().collect({ it.getParticipant() }).findAll({ it != null })
    }

    static constraints = {
        round unsigned: true
    }

    Field fieldAt(Field.Pos pos) {
        fieldAt(pos.x, pos.y)
    }

    Field fieldAt(int x, int y) {
        getFields().find({ it.getX() == x && it.getY() == y })
    }

    void addField(Field field) {
        assert field != null
        assert fieldAt(field.getPos()) == null

        addToFields(field)
    }

    static Battle create(Character character) {
        assert character != null

        character.save()
        return withTransaction {

            Battle battle = new Battle(active: character, position: character.getPosition())
            battle.save()

            def fields = []
            fields.addAll(Field.createHex(2).collect({ it + new Field.Pos(2, 0) }))
            fields.addAll(Field.createHex(2).collect({ it + new Field.Pos(-2, 0) }))

            fields
                .collect({ new Field(x: it.x, y: it.y) })
                .each({ try { battle.addField(it) } catch(AssertionError ignored) {} })

            battle.add(character)

            battle.save(failOnError: true)
        }
    }

    void add(Character character) {

        Field spawn = getFields().first()
        character.setField(spawn)
        character.save(failOnError: true)

    }

}
