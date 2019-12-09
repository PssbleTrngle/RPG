package rpg

class Field implements Serializable {

    int x, y, spawnFor

    static belongsTo = [ battle: Battle, participant: Participant ]

    static constraints = {
        /* id composite: [ 'x', 'y', 'battle' ] */
        participant nullable: true
    }

    Pos getPos() {
        new Pos(getX(), getY())
    }

    static class Pos {
        int x, y

        Pos(int x, int y) {
            this.x = x
            this.y = y
        }

        Pos plus(Pos other) {
            if(!other) return null;
            return new Pos(this.x + other.x, this.y + other.y);
        }

    }

    static Set<Pos> createHex(int radius = 1) {
        def area = new ArrayList<Pos>();
        for(int x = -radius; x <= radius; x++)
            for(int y = -radius; y <= radius; y++)
                if(Math.abs(x + y) <= radius)
                    area.add(new Pos(x, y))
        return area;
    }

}
