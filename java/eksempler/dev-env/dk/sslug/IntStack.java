package dk.sslug;

/**
 * Denne klasse repr�senterer en simpel stak, der
 * lagrer heltal.
 *
 * @author Jonas Kongslund (jonas@kongslund.dk)
 * @version 1.1
 */
public class IntStack
{
  /** Antal elementer i stakken */
  protected int count;

  /**
   * Indeholder stakkens elementer. Elementerne er placeret
   * i <code>elements[0...count-1]</code>.
   * <p>
   * Toppen af stakken er <code>count-1</code>
   * n�r <code>count>0</code> og ellers udefineret.
   *
   * @see #pop()
   * @see #push(int)
   */
  protected int[] elements;

  /**
   * Standardkonstrukt�r for denne klasse.
   */
  public IntStack()
  {
    /* �velse: implementer metoden s�dan at
       elements og count initialiseres til
       nogle fornuftige v�rdier */
  }

  /**
   * Fjerner og returnerer det �verste tal p� stakken.
   *
   * @return int Det �verste tal p� stakken
   * @exception java.util.EmptyStackException
   *            hvis stakken er tom
   */
  public int pop() throws java.util.EmptyStackException
  {
    /* �velse: implementer metoden */
    return -1;
  }

  /**
   * Placerer det angivne tal �verst p� stakken.
   *
   * @param element Tallet der skal l�gges p� stakken
   */
  public void push(int element)
  {
    /* �velse: implementer metoden s� stakken
       udvides s�fremt den er fyldt */
  }

  /**
   * Placerer det angivne tal �verst p� stakken.
   *
   * @param element Tallet der skal l�gges p� stakken
   * @deprecated Siden version 1.1; Metoden er
   *   erstattet af <code>push(int)</code>.
   * @see #push(int)
   */
  public void skub(int element)
  {
    push(element);
  }
}
