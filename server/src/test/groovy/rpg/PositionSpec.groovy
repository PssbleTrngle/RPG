package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class PositionSpec extends Specification implements DomainUnitTest<Position> {

    def setup() {}

    def cleanup() {}

    void "floor only in dungeon"() {
        boolean inDungeon = domain.getDungeon() != null
        expect:
        inDungeon || (domain.getFloor() == 1 && domain.getAttempts() == 0 && !domain.isFoundStairs())
    }
}
